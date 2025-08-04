<?php // routes/breadcrumbs.php

// Note: Laravel will automatically resolve `Breadcrumbs::` without
// this import. This is nice for IDE syntax and refactoring.
use Diglactic\Breadcrumbs\Breadcrumbs;

// This import is also not required, and you could replace `BreadcrumbTrail $trail`
//  with `$trail`. This is nice for IDE type checking and completion.
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;


Breadcrumbs::for('unit', function (BreadcrumbTrail $trail) {
    $trail->push('Unit', route('unit'));
});

// Home > Blog
// Breadcrumbs::for('unit.edit.form', function (BreadcrumbTrail $trail) {
//     $trail->parent('unit');
//     $trail->push('Edit', route('unit.edit.form'));
// });

// Home > Blog > [Category]
Breadcrumbs::for('unit.edit', function (BreadcrumbTrail $trail, $unit) {
    $trail->parent('unit');
    $trail->push($unit->serial, route('unit.edit', $unit));
});
