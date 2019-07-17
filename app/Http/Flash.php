<?php

namespace App\Http;

use Illuminate\Support\Arr;

class Flash
{

    protected $key = 'flash';

    public function info($title, $message = null, $overlay = false, $html = false)
    {
        return $this->create('info', $title, $message, $overlay, $html);
    }

    public function create($type, $title, $message = null, $overlay = false, $html = false)
    {
        if ($html) {
            $message = str_replace(["\r\n", "\r", "\n"], "<br/>", $message);
        }

        session()->flash($this->key, [
            'type' => $type,
            'title' => $title,
            'message' => is_array($message) ? Arr::flatten($message) : $message,
            'overlay' => $overlay,
            'html' => $html
        ]);
    }

    public function success($title, $message = null, $overlay = false, $html = false)
    {
        return $this->create('success', $title, $message, $overlay, $html);
    }

    public function error($title, $message = null, $overlay = false, $html = false)
    {
        return $this->create('error', $title, $message, $overlay, $html);
    }

    public function warning($title, $message = null, $overlay = false, $html = false)
    {
        return $this->create('warning', $title, $message, $overlay, $html);
    }

    public function question($title, $message = null, $overlay = false, $html = false)
    {
        return $this->create('question', $title, $message, $overlay, $html);
    }

}
