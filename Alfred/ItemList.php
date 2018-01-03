<?php namespace Alfred;

class ItemList
{
    public $items = [];

    public function add(Item $item)
    {
        $this->items[] = $item;
    }

    public function pop()
    {
        return array_pop($this->items);
    }

    public function shift()
    {
        return array_shift($this->items);
    }

    public function output($mode = 'json')
    {
        if ($mode == 'xml') {
            return $this->outputXML();
        }

        $output = json_encode([ 'items' => $this->items ]);

        return $output;
    }

    public function outputXML()
    {
        $output = '<?xml version="1.0"?><items>';
        foreach ($this->items as $item) {
            $output .= $item->output();
        }
        $output .= '</items>';
        return $output;
    }
}
