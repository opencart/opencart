<?php namespace Todaymade\Daux\Format\Confluence;

class DetailsToExpand
{
    public function convert(string $content): string
    {
        // convert <namespace:tag to <namespace__tag for DOMDocument to be happy
        $content = preg_replace('/<(\/?)(\w+):(\w+)/', '<\1\2___\3', $content);

        // Extract CDATA to not avoid it being corrupted by the parser
        $strtr = [];
        $content = preg_replace_callback(
            '/<!\[CDATA\[(.*?)]]>/ms',
            function ($matches) use (&$strtr) {
                $key = '%' . md5($matches[0]) . '%';
                $strtr[$key] = $matches[0];

                return $key;
            },
            $content
        );

        $dom = new \DOMDocument();

        // Ignore errors when parsing unknown tags, add a wrapping tag to not skew how tags are parsed
        libxml_use_internal_errors(true);
        $dom->loadHTML("<wrapper>$content</wrapper>", LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED);
        libxml_clear_errors();

        $detailElements = $dom->getElementsByTagName('details');

        $count = $detailElements->length;
        for ($i = $count - 1; $i >= 0; --$i) {
            $this->convertOne($detailElements->item($i));
        }

        // Export without the wrapper
        $validNodes = $dom->childNodes->item(0)->childNodes;
        $finalContent = '';
        foreach ($validNodes as $node) {
            $finalContent .= $dom->saveHTML($node);
        }

        // restore CDATA content
        $finalContent = strtr($finalContent, $strtr);

        // restore namespace tags
        return preg_replace('/<(\/?)(\w+)___(\w+)/', '<\1\2:\3', $finalContent);
    }

    protected function findSummary(\DOMElement $element): ?\DOMElement
    {
        if ($element->childElementCount == 0) {
            return null;
        }

        foreach ($element->childNodes as $child) {
            if ($child->nodeName == 'summary') {
                return $child;
            }
        }

        return null;
    }

    protected function isWithinCodeBlock(\DOMElement $element)
    {
        $current = $element->parentNode;
        while ($current != null) {
            if ($current->nodeName == 'ac___structured-macro'
                && $current->getAttribute('ac:name') === 'code') {
                return true;
            }

            $current = $current->parentNode;
        }

        return false;
    }

    protected function convertOne(\DOMElement $element)
    {
        /*
        <ac:structured-macro ac:name="expand">
          <ac:parameter ac:name="title">This is my message</ac:parameter>
          <ac:rich-text-body>
            <p>This text is <em>hidden</em> until you expand it.</p>
          </ac:rich-text-body>
        </ac:structured-macro>
        */

        if ($this->isWithinCodeBlock($element)) {
            return;
        }

        $summary = $this->findSummary($element);
        if (!$summary) {
            // If we can't find a title we don't try to convert to expandable
            return;
        }

        $document = $element->ownerDocument;

        // Create new title node
        $title = $document->createElement('ac:parameter');
        $title->setAttribute('ac:name', 'title');
        $titleChildNodes = $summary->childNodes;
        while ($titleChildNodes->length > 0) {
            $title->appendChild($titleChildNodes->item(0));
        }
        $element->removeChild($summary);

        // Create body node
        $body = $document->createElement('ac:rich-text-body');
        $childNodes = $element->childNodes;
        while ($childNodes->length > 0) {
            $body->appendChild($childNodes->item(0));
        }

        // Assemble the macro
        $macro = $document->createElement('ac:structured-macro');
        $macro->setAttribute('ac:name', 'expand');

        $macro->appendChild($document->createTextNode("\n"));
        $macro->appendChild($title);
        $macro->appendChild($document->createTextNode("\n"));
        $macro->appendChild($body);
        $macro->appendChild($document->createTextNode("\n"));

        $element->parentNode->replaceChild($macro, $element);
    }
}
