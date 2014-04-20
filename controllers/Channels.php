<?php namespace RainLab\Forum\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use RainLab\Forum\Models\Channel;

/**
 * Channels Back-end Controller
 */
class Channels extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('RainLab.Forum', 'forum', 'channels');
    }

    public function reorder()
    {
        $this->pageTitle = 'Reorder Channels';

        $toolbarConfig = $this->makeConfig();
        $toolbarConfig->buttons = '@/plugins/rainlab/forum/controllers/channels/_reorder_toolbar.htm';

        $this->vars['toolbar'] = $this->makeWidget('Backend\Widgets\Toolbar', $toolbarConfig);
        $this->vars['records'] = Channel::make()->getEagerRoot();
    }

    public function reorder_onMove()
    {
        $sourceNode = Channel::find(post('sourceNode'));
        $targetNode = post('targetNode') ? Channel::find(post('targetNode')) : null;

        if ($sourceNode == $targetNode)
            return;

        switch (post('position')) {
            case 'before': $sourceNode->moveBefore($targetNode); break;
            case 'after': $sourceNode->moveAfter($targetNode); break;
            case 'child': $sourceNode->makeChildOf($targetNode); break;
            default: $sourceNode->makeRoot(); break;
        }

        // $this->vars['records'] = Channel::make()->getEagerRoot();
        // return ['#reorderRecords' => $this->makePartial('reorder_records')];
    }
}