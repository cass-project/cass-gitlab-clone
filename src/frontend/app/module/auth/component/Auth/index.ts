import {Component} from "angular2/core";
import {CORE_DIRECTIVES} from "angular2/common";

import {ModalComponent} from "../../../modal/component/index";
import {SignInComponent} from "../SignIn/index";
import {SignUpComponent} from "../SignUp/index";
import {SignOutComponent} from "../SignOut/index";
import {AuthComponentService} from "./service";

@Component({
    selector: 'cass-auth',
    template: require('./template.html'),
    directives: [
        CORE_DIRECTIVES,
        ModalComponent,
        SignInComponent,
        SignUpComponent,
        SignOutComponent
    ],
    styles: [
        require('./style.shadow.scss')
    ]
})
export class AuthComponent
{
    constructor(private service: AuthComponentService) {}
}