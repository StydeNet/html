<?php

namespace Styde\Html\FormModel;

use Styde\Html\Facades\Html;
use Styde\Html\FieldBuilder;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Traits\Macroable;
use Mockery\Exception\BadMethodCallException;

class FieldCollection
{
    use Macroable {
        Macroable::__call as macroCall;
    }

    /**
     * @var \Styde\Html\FieldBuilder
     */
    protected $fieldBuilder;
    
    /**
     *  List of the fields form.
     *
     * @var array
     */
    protected $fields = [];

    /**
     * Creates a new FieldCollection class.
     *
     * @param \Styde\Html\FieldBuilder $fieldBuilder
     */
    public function __construct(FieldBuilder $fieldBuilder)
    {
        $this->fieldBuilder = $fieldBuilder;
    }

    /**
     * Returns true , false otherwise.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->fields);
    }

    /**
     * Dynamically handle calls to the field builder.
     *
     * @param  string $method
     * @param  array $params
     *
     * @return \Styde\Html\FieldBuilder
     * @throws \BadMethodCallException
     */
    public function __call($method, $params)
    {
        if ($this->hasMacro($method)) {
            return $this->macroCall($method, $params);
        }

        if (!empty($params)) {
            return $this->add($params[0], $method);
        }

        throw new BadMethodCallException(sprintf(
            'Call to undefined method %s::%s()', static::class, $method
        ));
    }

    /**
     * Call the render method.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Get a field by name.
     *
     * @param  string $name
     *
     * @return Field
     */
    public function __get($name)
    {
        return $this->fields[$name];
    }

    /**
     * Add a field.
     *
     * @param string $name
     * @param string $type
     *
     * @return \Styde\Html\FormModel\Field
     */
    public function add($name, $type = 'text')
    {
        return $this->fields[$name] = new Field($this->fieldBuilder, $name, $type);
    }

    /**
     * Add a tag.
     *
     * @param $tag
     * @param string $content
     * @param array $attributes
     * @return HtmlString
     * @internal param string $name
     * @internal param string $type
     *
     */
    public function tag($tag, $content = '', array $attributes = [])
    {
        return $this->fields[] = Html::tag($tag, $content, $attributes);
    }

    /**
     * Get the fields array.
     *
     * @return array
     */
    public function all()
    {
        return $this->fields;
    }

    /**
     * Get only the fields from the fields array.
     *
     * @return array
     */
    public function onlyFields()
    {
        return array_filter($this->fields, function ($field) {
            return $field instanceof Field;
        });
    }

    /**
     * Render all the fields.
     *
     * @return string
     */
    public function render()
    {
        $html = '';

        foreach ($this->all() as $field) {
            $html .= $field->render();
        }

        return new HtmlString($html);
    }
}
