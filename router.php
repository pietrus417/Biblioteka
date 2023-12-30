<?php
$request = $_SERVER['REQUEST_URI'];

switch ($request) {
    case '/add-book':
        include 'add_book.php';
        break;
    case '/delete-book':
        include 'delete_book.php';
        break;
    case '/edit-book':
        include 'edit_book.php';
        break;
    case '/view-books':
        include 'view_books.php';
        break;
    default:
        include 'index.php';
        break;
}