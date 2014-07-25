<?php
namespace Todo\Model;
use UnexpectedValueException ;

class Todo
{
    /**
     * Indentify todo item
     * @var int
     */
    protected $id;

    /**
     * Title of task
     * @var string max 300 char
     */
    protected $title;

    /**
     * percent complete task
     * @var int
     */
    protected $complete;

    public function __construct($title,$id=0,$complete=0)
    {
        $this->id = $id;
        $this->complete = $complete;
        $this->title = $title;
    }

    /**
     * Gets the Indentify todo item.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Gets the Title of task.
     *
     * @return string max 300 char
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Gets the percent complete task.
     *
     * @return int
     */
    public function getComplete()
    {
        return $this->complete;
    }

    /**
     * Sets the percent complete task.
     *
     * @param int $complete the complete 
     *
     * @return self
     */
    public function setComplete($complete)
    {
        if ($complete < 0 || $complete > 100) {
            throw new UnexpectedValueException ("please set complete value in range [1..100]");
        }
        $this->complete = $complete;

        return $this;
    }
}