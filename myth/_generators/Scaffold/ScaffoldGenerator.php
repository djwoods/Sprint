<?php
/**
 * Sprint
 *
 * A set of power tools to enhance the CodeIgniter framework and provide consistent workflow.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package     Sprint
 * @author      Lonnie Ezell
 * @copyright   Copyright 2014-2015, New Myth Media, LLC (http://newmythmedia.com)
 * @license     http://opensource.org/licenses/MIT  (MIT)
 * @link        http://sprintphp.com
 * @since       Version 1.0
 */

use Myth\CLI as CLI;

class ScaffoldGenerator extends \Myth\Forge\BaseGenerator {

	protected $fields = '';

	protected $model_name = '';

	//--------------------------------------------------------------------

	public function run($segments = [ ], $quiet = false)
	{
		$name = array_shift( $segments );

		// If we didn't attach any fields, then we
		// need to generate the barebones here.
		$this->fields = CLI::option('fields');
		if (empty($this->fields))
		{
			$this->fields = "id:int id:id title:string created_on:datetime modified_on:datetime";
		}

		// Perform the steps.
		$this->makeMigration($name);
		$this->makeSeed($name);
		$this->makeModel($name);
		$this->makeController($name);

		$this->readme();
	}

	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// Step Methods
	//--------------------------------------------------------------------

	protected function makeMigration($name)
	{
		$name = strtolower($name);

		$mig_name = "create_{$name}_table";

		$this->generate("migration {$mig_name}", "-fields '{$this->fields}'", true);
	}

	//--------------------------------------------------------------------

	public function makeSeed($name)
	{
	    $this->generate("seed {$name}", null, true);
	}

	//--------------------------------------------------------------------


	public function makeModel($name)
	{
	    $this->load->helper('inflector');

		$name = singular($name);
		$this->model_name = $name;

		$this->generate("model {$name}", "-table $name -primary_key id", true);
	}

	//--------------------------------------------------------------------

	public function makeController($name)
	{
		$this->generate("controller {$name}", "-model {$this->model_name} -create_views -fields '{$this->fields}'", true);
	}

	//--------------------------------------------------------------------

}