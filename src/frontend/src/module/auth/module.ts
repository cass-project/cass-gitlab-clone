import {AuthModalsService} from "./component/Auth/modals";
import {AuthRESTService} from "./service/AuthRESTService";
import {AuthService} from "./service/AuthService";
import {AuthComponent} from "./component/Auth/index";
import {OAuth2Component} from "./component/OAuth2/index";
import {SignInComponent} from "./component/SignIn/index";
import {SignUpComponent} from "./component/SignUp/index";
import {SignInByAPIKeyComponent} from "./component/SignInByAPIKey/index";
import {SignOutComponent} from "./component/SignOut/index";

export const CASSAuthModule = {
    declarations: [
        AuthComponent,
        OAuth2Component,
        SignInComponent,
        SignUpComponent,
        SignOutComponent,
        SignInByAPIKeyComponent,
    ],
    providers: [
        AuthModalsService,
        AuthRESTService,
        AuthService,
    ]
};

const AUTH_VERSION = "1";
const LOCAL_STORAGE_API_KEY = 'cass.module.auth.check';

if(window.localStorage['cass.module.auth.check'] === undefined) {
    delete window.localStorage['api_key'];
           window.localStorage['cass.module.auth.check'] = AUTH_VERSION;
}