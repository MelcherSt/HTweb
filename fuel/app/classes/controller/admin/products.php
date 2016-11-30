<?php
class Controller_Admin_Products extends Controller_Admin
{

	public function action_index()
	{
		$data['products'] = Model_Product::find('all');
		$this->template->title = "Products";
		$this->template->content = View::forge('admin/products/index', $data);

	}

	public function action_view($id = null)
	{
		$data['product'] = Model_Product::find($id);

		$this->template->title = "Product";
		$this->template->content = View::forge('admin/products/view', $data);

	}

	public function action_create()
	{
		if (Input::method() == 'POST')
		{
			$val = Model_Product::validate('create');

			if ($val->run())
			{
				$product = Model_Product::forge(array(
					'title' => Input::post('title'),
					'notes' => Input::post('notes'),
					'price' => Input::post('price'),
					'paid_by' => Input::post('paid_by'),
					'settled' => Input::post('settled'),
				));

				if ($product and $product->save())
				{
					Session::set_flash('success', e('Added product #'.$product->id.'.'));

					Response::redirect('admin/products');
				}

				else
				{
					Session::set_flash('error', e('Could not save product.'));
				}
			}
			else
			{
				Session::set_flash('error', $val->error());
			}
		}

		$this->template->title = "Products";
		$this->template->content = View::forge('admin/products/create');

	}

	public function action_edit($id = null)
	{
		$product = Model_Product::find($id);
		$val = Model_Product::validate('edit');

		if ($val->run())
		{
			$product->title = Input::post('title');
			$product->notes = Input::post('notes');
			$product->price = Input::post('price');
			$product->paid_by = Input::post('paid_by');
			$product->settled = Input::post('settled');

			if ($product->save())
			{
				Session::set_flash('success', e('Updated product #' . $id));

				Response::redirect('admin/products');
			}

			else
			{
				Session::set_flash('error', e('Could not update product #' . $id));
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$product->title = $val->validated('title');
				$product->notes = $val->validated('notes');
				$product->price = $val->validated('price');
				$product->paid_by = $val->validated('paid_by');
				$product->settled = $val->validated('settled');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('product', $product, false);
		}

		$this->template->title = "Products";
		$this->template->content = View::forge('admin/products/edit');

	}

	public function action_delete($id = null)
	{
		if ($product = Model_Product::find($id))
		{
			$product->delete();

			Session::set_flash('success', e('Deleted product #'.$id));
		}

		else
		{
			Session::set_flash('error', e('Could not delete product #'.$id));
		}

		Response::redirect('admin/products');

	}

}
