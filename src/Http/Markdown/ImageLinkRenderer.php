<?php

namespace DarkBlog\Http\Markdown;

use Illuminate\Support\Facades\Storage;
use League\CommonMark\ElementRendererInterface;
use League\CommonMark\Inline\Element\Image;
use League\CommonMark\Inline\Element\AbstractInline;
use League\CommonMark\Inline\Renderer\InlineRendererInterface;
use League\CommonMark\HtmlElement;

class ImageLinkRenderer implements InlineRendererInterface
{
    public function render(AbstractInline $inline, ElementRendererInterface $htmlRenderer)
    {
        if ( ! ($inline instanceof Image)) {
            throw new \InvalidArgumentException('Incompatible inline type: ' . get_class($inline));
        }

        $attrs = array();

        $image_url = $inline->getUrl();
        if ($this->imageFileExists($image_url)) {
            $attrs['src'] = asset('storage/'.$image_url);
        }
        else {
            $attrs['href'] = route('blog.upload', ['file' => $image_url]);

            return new HtmlElement('a', $attrs, $htmlRenderer->renderInlines($inline->children()));
        }

        if (isset($inline->data['title'])) {
            $attrs['title'] = $inline->data['title'];
        }

        $attrs['class'] = 'img-fluid';

        return new HtmlElement('img', $attrs, $htmlRenderer->renderInlines($inline->children()));
    }

    private function imageFileExists($image_name)
    {
        return Storage::exists('/public/' . $image_name);
    }
}
