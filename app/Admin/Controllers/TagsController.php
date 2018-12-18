<?php

namespace App\Admin\Controllers;

use App\Tag;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class TagsController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('标签列表')
            ->description('description')
            ->body($this->grid());
    }

    /**
     * Edit interface.
     * @param mixed   $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('编辑标签')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('添加标签')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Tag);

        $grid->id('ID');
        $grid->tag('标签');
        $grid->title('标题');
        $grid->subtitle('副标题');
        $grid->page_image('标签图片')->image('', 50, 50);
        $grid->meta_description('标签介绍');
        $states = [
            'on'  => ['value' => 1, 'text' => '升序', 'color' => 'primary'],
            'off' => ['value' => 0, 'text' => '降序', 'color' => 'default'],
        ];
        $grid->reverse_direction('文章排列')->switch($states);

        $grid->disableFilter();
        $grid->disableExport();
        $grid->actions(function ($actions) {
            $actions->disableView();
        });

        return $grid;
    }

    /**
     * Make a form builder.
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Tag);

        $form->text('tag', '标签');
        $form->text('title', '标题');
        $form->text('subtitle', '副标题');

        $folder_dir = "uploads/images/tags/" . date("Ym/d", time());
        $form->image('page_image', '标签图片')->move($folder_dir, function ($file) {
            $extension = $file->guessExtension();
            return time() . '_' . str_random(10) . '.' . $extension;
        });

        $form->text('meta_description', '标签介绍');
        $form->switch('reverse_direction', '文章按时间升序排列（默认降序）');

        return $form;
    }
}
