<?php

namespace ilateral\SilverStripe\CustomMenus\Model;

use SilverStripe\ORM\ArrayList;

class CustomMenuList extends ArrayList
{
    /**
     * The @link CustomMenuHolder assotiated with this list
     *
     * @var CustomMenuHolder
     */
    protected $holder;

    /**
     * Get the @link CustomMenuHolder assotiated with this list
     *
     * @return  CustomMenuHolder
     */
    public function getHolder()
    {
        return $this->holder;
    }

    /**
     * Set the @link CustomMenuHolder assotiated with this list
     *
     * @param  CustomMenuHolder $holder The CustomMenuHolder assotiated with this list
     *
     * @return self
     */
    public function setHolder(CustomMenuHolder $holder)
    {
        $this->holder = $holder;

        return $this;
    }
}