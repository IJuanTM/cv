<?php

namespace app\Models;

class PageModel
{
    private object $obj;
    private array $url;

    public function __construct(string $page, array $subpages, array $params)
    {
        $this->url = [
            'page' => $page,
            'subpages' => $subpages,
            'params' => $params
        ];
    }

    public function getUrl(): array
    {
        return $this->url;
    }

    public function getObj(): object
    {
        return $this->obj;
    }

    public function setObj(object $obj): void
    {
        $this->obj = $obj;
    }
}
