<?php

namespace DarkBlog;

use App\Models\Document;
use App\Models\DocumentLink;
use DarkBlog\Http\Markdown\ImageLinkRenderer;
use Illuminate\Support\Facades\Storage;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment;
use League\CommonMark\DocParser;
use League\CommonMark\HtmlRenderer;
use League\CommonMark\Inline\Element\Image;

class Parser
{
    private $raw_text;
    private $working_text;
    private $html_text;

    private $document;

    private $existing_links;

    private $links;

    static public function html($text)
    {
        $environment = Environment::createCommonMarkEnvironment();
        $environment->setConfig([
//            'html_input' => 'strip',
        ]);

        // We want custom handle image rendering
        $environment->addInlineRenderer(Image::class, new ImageLinkRenderer());

        $parser = new DocParser($environment);
        $htmlRenderer = new HtmlRenderer($environment);

        $document = $parser->parse($text);
        return $htmlRenderer->renderBlock($document);
    }

    public function __construct(Document $document)
    {
        $this->document = $document;

        $this->raw_text = $document->body;
    }

    public function links()
    {
        $links = [];

        $allowed_string = "A-Z|a-z|0-9|\s|_";

        # Match a link having the form [[namespace:link|alternate]]trail
        $needle_no_alt = "/^([{$allowed_string}]+)]](.*)\$/sD";
        $needle_w_alt = "/^([{$allowed_string}]+)(?:\|)(.*)]](.*)\$/sD";
        $image_wiki = "/^Image:(.*)]](.*)\$/i";

        $text_lines = explode("\n", $this->raw_text);

        foreach ($text_lines as $line) {

            #split the entire text string on occurances of [[
            $a = explode('[[', ' ' . $line);

            # Loop for each link
            for ($k = 0; isset($a[$k]); $k++) {
                $line_section = $a[$k];
                if (preg_match($needle_w_alt, $line_section, $matches)) {
                    $title = new Title($matches[1]);
                    $links[$title->slug()] = 'Document';
                }
                elseif (preg_match($needle_no_alt, $line_section, $matches)) {
                    $title = new Title($matches[1]);
                    $links[$title->slug()] = 'Document';
                }
                elseif (preg_match($image_wiki, $line_section, $matches)) {
                    $links[$matches[1]] = 'File';
                }
            }
        }

        $this->links = $links;

        return $this->links;
    }

    public function parse()
    {
        $this->working_text = $this->raw_text;

        $this->loadExistingLinks();

        $this->working_text = Markdown::defaultTransform($this->working_text);

        $this->parseWikiTags();

        $this->html_text = $this->working_text;

        return $this->html_text;
    }

    private function parseWikiTags()
    {
        $output = '';

        $allowed_string = 'A-Z|a-z|0-9|\s|_';

        # Match a link having the form [[namespace:link|alternate]]trail
        $needle_no_alt = "/^([{$allowed_string}]+)]](.*)\$/sD";
        $needle_w_alt = "/^([{$allowed_string}]+)(?:\|)(.*)]](.*)\$/sD";
        $image_wiki = "/^Image:(.*)]](.*)\$/i";

        $text_lines = explode("\n", $this->working_text);

        foreach ($text_lines as $line) {

            // check to see if we are inside a form or inside a no wiki
            // if we are we don't want to parse any content in there
            $nowiki = false;
            if (preg_match('/<form/iS', $line) || preg_match('/<nowiki/iS', $line)) {
                $nowiki = true;
                $output .= $line . "\n";
                continue;
            }
            // if we are closing out the form we can start processing again
            elseif (preg_match('/<\\/form/iS', $line) || preg_match('/<\\/nowiki/iS', $line)) {
                $nowiki = false;
                $output .= $line . "\n";
                continue;
            }

            // if we are inside the form skip these lines
            if ($nowiki) {
                $output .= $line . "\n";
                continue;
            }

            // split the string on occurences of [[
            $a = explode('[[', $line);

            # Loop for each link
            for ($k = 0; isset($a[$k]); $k++) {
                $line_section = $a[$k];

                // INTERNAL LINK WITH ALT TEXT
                if (preg_match($needle_w_alt, $line_section, $matches)) {

                    // get the pieces of the link
                    $title = new Title($matches[1]);
                    $display = $matches[2];
                    $tail = $matches[3];

                    // see if this page exists
                    if ($this->linkExists($title->slug())) {
                        $output .= '<a href="/Blackboard/' . $title->upperCase() . '">' . $display . '</a>';
                        $output .= $tail;
                    }
                    else { // page does not exist
                        $output .= '<a href="/Blackboard/' . $title->upperCase() . '" class="missing">' . $display . '</a>';
                        $output .= $tail;
                    }
                }
                // INTERNAL LINK NO ALT TEXT
                elseif (preg_match($needle_no_alt, $line_section, $matches)) {

                    // get the pieces of the link
                    $title = new Title($matches[1]);
                    $tail = $matches[2];

                    // see if this page exists
                    if ($this->linkExists($title->slug())) {
                        $output .= '<a href="/Blackboard/' . $title->upperCase() . '">' . $title->formatted() . '</a>';
                        $output .= $tail;
                    }
                    else { // page does not exist
                        $output .= '<a href="/Blackboard/' . $title->upperCase() . '" class="missing">' . $title->formatted() . '</a>';
                        $output .= $tail;
                    }
                }
                // IMAGE TAG
                elseif (preg_match($image_wiki, $line_section, $matches)) {
                    $image_tag = $this->doImage($matches[1]);
                    $tail = $matches[2];
                    $output .= $image_tag . $tail;
                }
                else {
                    $output .= $a[$k];
                }

                $output .= "\n";
            } // foreach line

            $this->working_text = $output;
        }
    }

    private function doImage($image_string)
    {
        if ($this->linkExists($image_string)) {
            return '<img src="' . Storage::url($image_string) . '">';
        }

        $form = '<form action="/Blackboard/file" method="POST" id="uploadFileForm" enctype="multipart/form-data">';
        $form .= csrf_field();
        $form .= '<input type="hidden" name="link_id" id="link_id" value="' . $this->document->id . '">
            <input type="hidden" name="name" id="name" value="' . $image_string . '">
            <input type="file" name="fileToUpload" id="fileToUpload">
            <input type="submit" class="btn btn-primary" value="Upload Image" name="submit">
        </form>';

        return $form;
    }

    private function linkExists($link_name)
    {
        foreach ($this->existing_links as $link) {
            if ($link->name == $link_name) {
                return true;
            }
        }

        return false;
    }

    private function loadExistingLinks()
    {
        $this->existing_links = DocumentLink::where('document_id', $this->document->id)
            ->where('link_id', '>', 0)
            ->get();
    }
}
