<?php

/**
 * Serializer for a browsable html result page.
 * @author Christian Blank <c.blank@notthatbad.net>
 */
class BrowsableHtmlSerializer extends ViewableData implements IRestSerializer {

    /**
     * @config
     */
    private static $is_active = true;

    /**
     * The content type
     * @var string
     */
    private $contentType = "text/html";

    /**
     * The given data will be serialized into an html string using a Silverstripe template.
     *
     * @param array $data
     * @return string an html string
     */
    public function serialize($data) {
        $list = $this->recursive($data, 1);
        return $this->renderWith(['BrowsableResult', 'Controller'], ['Data' => ArrayList::create($list)]);
    }

    public function contentType() {
        return $this->contentType;
    }

    private function recursive($data, $level) {
        $list = [];
        if(is_array($data)) {
            foreach ($data as $key => $value) {
                if(is_array($value)) {
                    $list[] = ArrayData::create(['Key' => $key, 'Value' => '', 'Heading' => true, 'Level' => $level]);
                    $list = array_merge($list, $this->recursive($value, $level+1));
                } else {
                    $list[] = ArrayData::create(['Key' => $key, 'Value' => $value, 'Level' => $level]);
                }
            }
        }
        return $list;
    }

    /**
     * Indicates if the serializer is active.
     * Serializers can be deactivated to use another implementation for the same mime type.
     *
     * @return boolean
     */
    public function active() {
        return $this->config()->get('is_active');
    }
}
