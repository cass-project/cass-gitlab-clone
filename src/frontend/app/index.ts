/// <reference path="./../typings/main.d.ts" />
/// <reference path="./../node_modules/angular2/typings/browser.d.ts" />

import 'es6-shim';
import 'es6-promise';
import 'reflect-metadata';
import 'rxjs/Rx';

require('zone.js');
require('bootstrap/dist/css/bootstrap.css');
require('./global.head.scss');

import {Component, provide} from 'angular2/core';
import {bootstrap} from 'angular2/platform/browser';
import {RouteConfig, ROUTER_DIRECTIVES, ROUTER_PROVIDERS} from 'angular2/router';
import {HTTP_PROVIDERS, BaseRequestOptions, RequestOptions, URLSearchParams} from 'angular2/http';
import {CORE_DIRECTIVES} from 'angular2/common';
import {Cookie} from 'ng2-cookies';

import {MainMenu} from './module/common/component/MainMenu/index'
import {AuthService} from './module/auth/service/AuthService';
import {AuthComponent} from './module/auth/index';
import {ProfileComponent} from './module/profile/index';
import {CatalogComponent} from './module/catalog/index';
import {CollectionComponent} from "./module/collection/index";
import {WorkInProgress} from "./module/common/component/WorkInProgress/index";
import {Nothing} from "./module/common/component/Nothing/index";

@Component({
    selector: 'cass-bootstrap',
    template: require('./template.html'),
    directives: [
        ROUTER_DIRECTIVES,
        CORE_DIRECTIVES,
        MainMenu
    ],
    providers: [
        AuthService
    ]
})
@RouteConfig([
    {
        useAsDefault: true,
        path: '/profile/...',
        name: 'Profile',
        component: ProfileComponent,
    },
    {
        path: '/auth/...',
        name: 'Auth',
        component: AuthComponent
    },
    {
        path: '/collection/...',
        name: 'Collection',
        component: CollectionComponent
    },
    {
        path: '/catalog/...',
        name: 'Catalog',
        component: CatalogComponent
    }
])
class App {}

class OAuthRequestOptions extends BaseRequestOptions {
    constructor () {
        super();

        this.headers.append('Content-type', 'application/json');

        if(AuthService.isSignedIn()) {
            this.headers.set('X-Api-Key', AuthService.getAuthToken().apiKey);
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    bootstrap(
        <any>App, [
            ROUTER_PROVIDERS,
            HTTP_PROVIDERS,
            provide(RequestOptions, {useClass: OAuthRequestOptions}),
            provide(Window, {useValue: window})
        ]).catch((err) => {
            console.log(err.message);
        }
    );
});