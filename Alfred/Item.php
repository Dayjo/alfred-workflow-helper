<?php
namespace Alfred;

class Item
{
    private $output = '';

    public function __construct(array $item)
    {
        $this->title          = $item['title'];
        $this->subtitle       = (!empty($item['subtitle']) ? $item['subtitle'] : '');
        $this->arg            = (!empty($item['arg']) ? $item['arg'] : str_replace('"', '', $this->title));
        $this->uid            = (!empty($item['uid']) ? $item['uid'] : md5($this->title));
        $this->autocomplete   = (!empty($item['autocomplete']) ? $item['autocomplete'] : '');
        $this->valid          = (!empty($item['valid']) ? $item['valid'] : true);

        if (!$this->uid) {
            $this->uid = md5($this->title);
        }

        // Clean up the output TODO: make better
        if (empty($this->arg) && $this->arg != null) {
            $this->arg = str_replace('"', '', $this->title);
        }

        return $this;
    }

    public function xml()
    {
        return '<item uid="'.$this->uid.'" autocomplete= arg="' . $this->arg . '" ><title>'.$this->title.'</title><subtitle>'.$this->subtitle.'</subtitle></item>';
    }
}
