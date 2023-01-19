<?php

return [
    'files' => [
        // 
        'list.blade.php' => [
            'list1' => 'changed var 1 into XXlist1',
            'list2' => 'changed var 2 into XXlist2',
            'list3' => 'changed var 3 into XXlist3',
        ],
        'edit.blade.php' => [
            'edit1' => 'changed edit1 into XXedit1',
            'edit2' => 'changed edit2 into XXedit2',
            'edit3' => 'changed edit3 into XXedit3',
        ]
    ],
    'config' => [
        'base_path' => 'template-file-generator/example-generator/crud-views',
        'base_path_prefix' => 'resource',
        'target_path' => 'views/users',
        'target_path_prefix' => 'resource'
    ],
];
