<?php

namespace Mdg\Frontend\Controllers;
//use Phalcon\Mvc\Model\Criteria;
//use Phalcon\Paginator\Adapter\Model as Paginator;
use \Category as Category;


class CategoryController extends ControllerBase
{
    /**
     * Searches for category
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "\Category", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $category = Category::find($parameters);
        if (count($category) == 0) {
            $this->flash->notice("The search did not find any category");

            return $this->dispatcher->forward(array(
                "controller" => "category",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $category,
            "limit"=> 10,
            "page" => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displayes the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a category
     *
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $category = Category::findFirstByid($id);
            if (!$category) {
                $this->flash->error("category was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "category",
                    "action" => "index"
                ));
            }

            $this->view->id = $category->id;

            $this->tag->setDefault("id", $category->id);
            $this->tag->setDefault("title", $category->title);
            $this->tag->setDefault("parent_id", $category->parent_id);
            $this->tag->setDefault("arr_child", $category->arr_child);
            $this->tag->setDefault("arr_parent", $category->arr_parent);
            $this->tag->setDefault("deeps", $category->deeps);
            
        }
    }

    /**
     * Creates a new category
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "category",
                "action" => "index"
            ));
        }

        $category = new Category();

        $category->title = $this->request->getPost("title");
        $category->parent_id = $this->request->getPost("parent_id");
        $category->arr_child = $this->request->getPost("arr_child");
        $category->arr_parent = $this->request->getPost("arr_parent");
        $category->deeps = $this->request->getPost("deeps");
        

        if (!$category->save()) {
            foreach ($category->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "category",
                "action" => "new"
            ));
        }

        $this->flash->success("category was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "category",
            "action" => "index"
        ));

    }

    /**
     * Saves a category edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "category",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $category = Category::findFirstByid($id);
        if (!$category) {
            $this->flash->error("category does not exist " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "category",
                "action" => "index"
            ));
        }

        $category->title = $this->request->getPost("title");
        $category->parent_id = $this->request->getPost("parent_id");
        $category->arr_child = $this->request->getPost("arr_child");
        $category->arr_parent = $this->request->getPost("arr_parent");
        $category->deeps = $this->request->getPost("deeps");
        

        if (!$category->save()) {

            foreach ($category->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "category",
                "action" => "edit",
                "params" => array($category->id)
            ));
        }

        $this->flash->success("category was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "category",
            "action" => "index"
        ));

    }

    /**
     * Deletes a category
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $category = Category::findFirstByid($id);
        if (!$category) {
            $this->flash->error("category was not found");

            return $this->dispatcher->forward(array(
                "controller" => "category",
                "action" => "index"
            ));
        }

        if (!$category->delete()) {

            foreach ($category->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "category",
                "action" => "search"
            ));
        }

        $this->flash->success("category was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "category",
            "action" => "index"
        ));
    }

}
