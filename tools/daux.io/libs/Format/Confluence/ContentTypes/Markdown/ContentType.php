<?php namespace Todaymade\Daux\Format\Confluence\ContentTypes\Markdown;

class ContentType extends \Todaymade\Daux\ContentTypes\Markdown\ContentType
{
    protected function createConverter()
    {
        return new CommonMarkConverter(['daux' => $this->config]);
    }

    protected function addJS()
    {
        return <<<'EOD'
            <ac:structured-macro ac:name="html">
              <ac:plain-text-body> <![CDATA[
            <script type="module">
            function daux_ready(fn) {
                if (document.readyState === "loading") {
                    document.addEventListener("DOMContentLoaded", fn);
                } else {
                    fn();
                }
            }

            function daux_loadCSS(url) {
                var head = document.getElementsByTagName("head")[0],
                    link = document.createElement("link");
                link.rel = "stylesheet";
                link.href = url;
                head.appendChild(link);
            }

            daux_ready(function() {
                var codeBlocks = document.querySelectorAll("pre > code.katex");
                if (codeBlocks.length) {
                    daux_loadCSS(`https://cdn.jsdelivr.net/npm/katex@0.16.7/dist/katex.min.css`);

                    import(`https://cdn.jsdelivr.net/npm/katex@0.16.7/dist/katex.mjs`).then(katex => {
                        [].forEach.call(codeBlocks, function(e) {
                            var content = e.innerHTML;
                            var p = document.createElement("p");
                            var span = document.createElement("span");
                            p.className = "katex-display";
                            p.appendChild(span);

                            var pre = e.parentElement;
                            pre.parentElement.insertBefore(p, pre);
                            pre.parentElement.removeChild(pre);

                            katex.default.render(content, span, {
                                throwOnError: false
                            });
                        });
                    });
                }
            });

            daux_ready(function() {
                var mermaidBlocks = document.querySelectorAll("pre.mermaid");
                if (mermaidBlocks.length) {
                    import("https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.esm.min.mjs").then(mermaid => {
                        mermaid.default.run({ nodes: mermaidBlocks });
                    })
                }
            });
            </script>
            ]]></ac:plain-text-body>
            </ac:structured-macro>

            EOD;
    }

    protected function doConversion($raw)
    {
        $content = parent::doConversion($raw);

        if ($this->config->isTruthy('__confluence__tex') || $this->config->isTruthy('__confluence__mermaid')) {
            $content .= $this->addJS();
        }

        // Reset for the next conversion
        $this->config['__confluence__tex'] = false;
        $this->config['__confluence__mermaid'] = false;

        return $content;
    }
}
