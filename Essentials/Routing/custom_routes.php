<?php
switch ($path) {
    case ADMINPATH:
        switch ($page) {
            case 'test':
                $page = 'index';
                break;
            default:
                break;
        }
        break;
    case USERPATH:
        switch ($page) {
            case 'test':
                $page = 'index';
                break;
            default:
                break;
        }
        break;
    default:
        switch ($page) {
            case 'about-us':
            case 'info':
                $page = 'about';
                break;
            case 'contattaci':
                $page = 'contacts';
                break;
            default:
                break;
        }
        break;
}