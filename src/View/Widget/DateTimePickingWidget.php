<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         3.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\View\Widget;

use Cake\View\Form\ContextInterface;
use Cake\View\Widget\WidgetInterface;
use Cake\I18n\Time;

/**
 * Basic input class.
 *
 * This input class can be used to render basic simple
 * input elements like hidden, text, email, tel and other
 * types.
 */
class DateTimePickingWidget implements WidgetInterface
{

    /**
     * StringTemplate instance.
     *
     * @var \Cake\View\StringTemplate
     */
    protected $_templates;

    /**
     * Constructor.
     *
     * @param \Cake\View\StringTemplate $templates Templates list.
     */
    public function __construct($templates)
    {
        $this->_templates = $templates;
    }

    /**
     * Render a text widget or other simple widget like email/tel/number.
     *
     * This method accepts a number of keys:
     *
     * - `name` The name attribute.
     * - `val` The value attribute.
     * - `escape` Set to false to disable escaping on all attributes.
     *
     * Any other keys provided in $data will be converted into HTML attributes.
     *
     * @param array $data The data to build an input with.
     * @param \Cake\View\Form\ContextInterface $context The current form context.
     * @return string
     */
    public function render(array $data, ContextInterface $context)
    {

		if( ! isset( $data['format'] ) ){
			$data['format'] = 'Y-m-d H:i';
		}

		if( ! isset( $data['class'] ) ){
			$data['class'] = 'datetimepicker';
		} else {
			$data['class'] .= ' datetimepicker';
		}
		
        $data += [
            'name' => '',
            'val' => null,
            'type' => 'text',
            'escape' => true,
            'templateVars' => []
        ];
		
        $data['value'] = $data['val'];
		
		if( ! empty( $data['value'] ) ){
			if(!is_object($data['value'] )){
				$data['value'] = new Time($data['value']);
			}
			$data['value'] = $data['value']->format($data['format']);
			
		}
	
		unset($data['val']);
		unset($data['format']);

        return $this->_templates->format('input', [
            'name' => $data['name'],
            'type' => $data['type'],
            'templateVars' => $data['templateVars'],
            'attrs' => $this->_templates->formatAttributes(
                $data,
                ['name', 'type']
            ),
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function secureFields(array $data)
    {
        if (!isset($data['name']) || $data['name'] === '') {
            return [];
        }

        return [$data['name']];
    }
}
