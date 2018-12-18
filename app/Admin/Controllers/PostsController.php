<?php

namespace App\Admin\Controllers;

use App\Post;
use App\Http\Controllers\Controller;
use App\Tag;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class PostsController extends Controller
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
            ->header('文章列表')
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
            ->header('编辑文章')
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
            ->header('发布文章')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Post);

        $grid->id('ID');
        $grid->title('标题');
        $grid->subtitle('副标题');
        $grid->tags('标签')->pluck('tag')->label();
        $grid->page_image('封面')->image('', 50, 50);
        $states = [
            'on'  => ['value' => 1, 'text' => '是', 'color' => 'danger'],
            'off' => ['value' => 0, 'text' => '否', 'color' => 'primary'],
        ];
        $grid->is_draft('是否草稿')->switch($states);
        $grid->published_at('发布时间');
        $grid->created_at('创建时间');

        $grid->disableExport();
        $grid->actions(function ($actions) {
            $actions->disableView();
        });

        $grid->filter(function ($filter) {
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            $filter->like('title', '标题');
            $filter->like('subtitle', '副标题');

        });

        return $grid;
    }

    /**
     * Make a form builder.
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Post);

        $form->text('title', '标题')->rules('required|min:2');
        $form->text('subtitle', '副标题')->rules('required');
        $form->multipleSelect('tags', '标签')->options(Tag::all()->pluck('tag', 'id'));
        $form->editor('content_raw', '内容');

        $folder_dir = "uploads/images/posts/" . date("Ym/d", time());
        $form->image('page_image', '封面图片')->move($folder_dir, function ($file) {
            $extension = $file->guessExtension();
            return time() . '_' . str_random(10) . '.' . $extension;
        });

        $form->text('meta_description', '文章描述')->rules('required');
        $form->switch('is_draft', '是否草稿');
        $form->datetime('published_at', '发布时间')->default(date('Y-m-d H:i:s'));

        return $form;
    }
}
