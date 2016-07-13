#entity_list.phtml Config And Usage
The entity_list.phtml page provides entity list pages with pagination, sorting/order by, and options to add action buttons/functionality for admins or users. It is integrated with this site's acl.

##Setup
------------
The file lives at:
module/Application/view/share/forms/entity_list.phtml

##1- Your List Page
On the phtml file/page you wish to have as a list page, list.phtml, for example, simply add a reference to this entity partial as it is, or should be, registered as a globally available partial in Application module.config.php.

###Example
module/Application/config/module.config.php
```php
'view_manager' => array(
	//...
	'template_map' => array(
	    //....
	    'forms/entity_list'       => __DIR__ . '/../view/share/forms/entity_list.phtml',
	),
	'template_path_stack' => array(
	    __DIR__ . '/../view',
	),
),
```
###Example
module/User/view/user/account/list.phtml
```php
<?php echo $this->partial("forms/entity_list"); ?>
```
##2- Controller Implementation

###Example
```php

public function listAction()
    {
        $user = $this->serviceLocator->get('user');
        $currentPage = $this->params()->fromRoute('page', 1);
        
        //orderby, order values whitelisted in list route config
        $orderby = $this->params()->fromRoute('orderby', 'name');
        $order = $this->params()->fromRoute('order', 'desc');

        $sql = new Select();
        $sql->columns([
            'score' => new \Zend\Db\Sql\Expression("MAX(score_pct)"),
            'pass'  => 'pass',
            'test_id' => 'test_id'
        ])
        ->from('x_users_tests_attempts')
        ->where(['user_id' => $user->getId()])
        ->group('test_id');
            
        $testModel = new Test();
        $result = $testModel->getSql()
        ->select()
        ->columns([
            'id',
            'name',
            'description',
            'duration',
        ])
        ->join(
            ['ta' => $sql
            ],
            'tests.id = ta.test_id',
            ['score','pass'],Select::JOIN_LEFT)
            ->where([
                'active' => 1
            ])->order("$orderby $order");
            
        

        $adapter = new PaginatorDbAdapter($result, $testModel->getAdapter());
        $paginator = new Paginator($adapter);

        $paginator->setCurrentPageNumber($currentPage);
        $paginator->setItemCountPerPage(4);

        $acl = $this->serviceLocator->get('acl');

        //top keys match db columns
        $columns = [
            'name'         =>['th_text'=>'name', 'th_attributes'=>['nowrap'=>'true'],'td_formats'=>['tcDefaultCellFormat'=>'%s']],
            'description'  =>['th_text'=>'description', 'th_attributes'=>['nowrap'=>'true'],'td_formats'=>['tcDefaultCellFormat'=>'%s']],
            'duration'     =>['th_text'=>'duration (minutes)', 'th_attributes'=>['nowrap'=>'true'],'td_formats'=>['tcDefaultCellFormat'=>'%s']],
            'score'        =>['th_text'=>'Score %', 'th_attributes'=>['nowrap'=>'true'], 'td_attributes'=>['class'=>'list_exam_score'],'td_formats'=>['tcDefaultCellFormat'=>'%s%%', null=>'%s']],
        ];

        $listActions = [
            'take'       =>['text'=>'take', 'styleClass'=>Application::BTN_TAKE_DEFAULT],
            'edit'       =>['text'=>'edit', 'styleClass'=>Application::BTN_EDIT_DEFAULT],
            'delete'     =>['text'=>'delete', 'styleClass'=>Application::BTN_DELETE_DEFAULT],
        ];
        return array(
            'entities'      => $paginator,
            'acl'           => $acl,
            'page'          => $currentPage,
            'orderby'       => $orderby,
            'order'         => $order,
            'columns'       => $columns,
            'listActions'   => $listActions,
            'pageTitle'     => 'Exam List',
            'route'         => 'exam',
            'controller'    => 'test'
        );
    }

```
The major components needed are demonstrated in the return array but here are more details about the listActions and columns arrays.

###$columns Array
```php
$columns = [
            'name'         =>['th_text'=>'name', 'th_attributes'=>['nowrap'=>'true'],'td_formats'=>['tcDefaultCellFormat'=>'%s']],
            'description'  =>['th_text'=>'description', 'th_attributes'=>['nowrap'=>'true'],'td_formats'=>['tcDefaultCellFormat'=>'%s']],
            'duration'     =>['th_text'=>'duration (minutes)', 'th_attributes'=>['nowrap'=>'true'],'td_formats'=>['tcDefaultCellFormat'=>'%s']],
            'score'        =>['th_text'=>'Score %', 'th_attributes'=>['nowrap'=>'true'], 'td_attributes'=>['class'=>'list_exam_score'],'td_formats'=>['tcDefaultCellFormat'=>'%s%%', null=>'%s']],
        ];
```
1. Keys need to match the data columns in the $paginator object.
2. th_text is the text output in the header cell and will be uppercased on first char.
3. th_attributes will be output as the th tag's attributes and is where you define class/id/style/etc
  1. the key is the attribute name and the value is the value as in:
```php
'th_attributes'=>['nowrap'=>'true', 'class'=>'myListHeader primaryColor']
```
Will result in the th cell having the attributes like:
```php
<th nowrap="true" class="myListHeader primaryColor">
```
4. In the table body the td_attributes will be output as the td tag's attributes and is where you define class/id/style/etc for the data cells.
  1. the key is the attribute name and the value is the value as in:
```php
'td_attributes'=>['nowrap'=>'true', 'class'=>'myListDataCell money']
```
Will result in the td cell having the attributes like:
```php
<td nowrap="true" class="myListDataCell money">
```
5. td_formats allows you to specify formats for data depending on what it might be. In the example the score column comes back with either
or a number like 80 for a percent. Defined as:
```php
'td_formats'=>['tcDefaultCellFormat'=>'%s%%', null=>'%s'
```
When the data is output to the cell if the data matches null in the td_formats I am specify it to be output as-is with no percent sign, just
leave blank BUT if otherwise the default will be used, tcDefaultCellFormat, in all other cases and tcDefaultCellFormat needs to be specified.
I could have instead specified to output 'Not Taken' for null. 

###$listActions Array
```php
$listActions = [
            'take'       =>['text'=>'take', 'styleClass'=>Application::BTN_TAKE_DEFAULT],
            'edit'       =>['text'=>'edit', 'styleClass'=>Application::BTN_EDIT_DEFAULT],
            'delete'     =>['text'=>'delete', 'styleClass'=>Application::BTN_DELETE_DEFAULT],
        ];
```
Defines the actions available to a given user as defined by your acl in your module's config file, module.config.php, and matching the key values.

1. 'text' is what will be displayed in the button.
2. 'styleClass' defines the button's class attribute.

###Routing And OrderBy And Order
The orderby and order need to be setup in module.confing.php file as a whitelist of parameters where the orderby match the columns in the paginator data:
####Example
```php
'list' => array(
                        'type'    => 'Segment',
                        'options' => array (
                            'route' => '/test/list[/page/:page][/orderby/:orderby][/order/:order]',
                            'constraints' => array(
                                'page'     => '[0-9]*',
                                'orderby' => 'name|description|duration|score',
                                'order' => 'asc|desc'
                            ),
                            'defaults' => array(
                                'controller'    => 'Test',
                                'action'        => 'list',
                                'page'          => '1',
                                'orderby' => 'name',
                                'order' => 'desc'
                            ),
                        )
                    )
```

