<?php
namespace Album\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Album implements InputFilterAwareInterface
{
    /**
     * @var int album id
     */ 
    public $id;

    /**
     * @var string aritst name
     */
    public $artist;

    /**
     * @var string album title
     */
    public $title;

    /**
     * @var InputFilterInterface
     */
    protected $inputFilter;

    public function exchangeArray(array $data = array())
    {
        $this->id = !empty($data['id']) ? $data['id'] : null;
        $this->artist = !empty($data['artist']) ? $data['artist'] : null;
        $this->title = !empty($data['title']) ? $data['title'] : null;
    }

    public function setInputFilter( InputFilterInterface $inputFilter) 
    {
        throw new \Exception('Not used');
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }
        $inputFilter = new InputFilter();

        $inputFilter->add(array(
            'name' => 'id',
            'required' => true,
            'filters' => array(
                array('name' => 'Int'),
            ),
        ));

        $inputFilter->add(array(
            'name' => 'artist',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ),
                ),
            ),
        ));

        $inputFilter->add(array(
            'name' => 'title',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ),
                )
            ),
        ));

        $this->inputFilter = $inputFilter;
        return $inputFilter;
    }

    public function getArrayCopy() {
        $data = get_object_vars($this);
        unset($data['inputFilter']);
        return $data;
    }
}