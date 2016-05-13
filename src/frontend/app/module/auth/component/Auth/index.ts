import {Component} from "angular2/core";
import {CORE_DIRECTIVES} from "angular2/common";

import {ModalComponent} from "../../../modal/component/index";
import {SignInComponent} from "../SignIn/index";
import {SignUpComponent} from "../SignUp/index";
import {AuthComponentService} from "./service";

@Component({
    selector: 'cass-auth',
    template: require('./template.html'),
    directives: [
        CORE_DIRECTIVES,
        ModalComponent,
        SignInComponent,
        SignUpComponent,
    ],
    styles: [
        require('./style.shadow.scss')
    ]
})
export class AuthComponent
{
    constructor(private service: AuthComponentService) {}

    signIn() {
        this.service.modals.openSignInModal();
    }

    signUp() {
        this.service.modals.openSignUpModal();
    }
}