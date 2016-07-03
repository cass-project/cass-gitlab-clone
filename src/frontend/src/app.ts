import {Component} from "angular2/core";
import {CORE_DIRECTIVES} from "angular2/common";
import {RouteConfig, ROUTER_DIRECTIVES} from "angular2/router";

import {AuthComponentService} from "./module/auth/component/Auth/service";
import {CommunityModalService} from "./module/community/service/CommunityModalService";
import {CommunityRESTService} from "./module/community/service/CommunityRESTService";
import {ThemeService} from "./module/theme/service/ThemeService";
import {AuthComponent} from "./module/auth/component/Auth/index";
import {AccountComponent} from "./module/account/index";
import {ProfileComponent} from "./module/profile/index";
import {SidebarComponent} from "./module/sidebar/index";
import {CommunityComponent} from "./module/community/index";
import {RouterOutlet} from "angular2/router";
import {LandingComponent} from "./module/landing/index";
import {RootRoute as ProfileRootRoute} from "./module/profile/route/RootRoute";
import {AuthService} from "./module/auth/service/AuthService";
import {ProfileSwitcherService} from "./module/profile/component/Modals/ProfileSwitcher/service";
import {ModalService} from "./module/modal/component/service";
import {CommunityRoute} from "./module/community/route/CommunityRoute/index";
import {MessageBusService} from "./module/message/service/MessageBusService/index";
import {MessageBusNotifications} from "./module/message/component/MessageBusNotifications/index";
import {HtmlComponent} from "./module/html/index";
import {CommunityService} from "./module/community/service/CommunityService";
import {CollectionRESTService} from "./module/collection/service/CollectionRESTService";
import {ProfileRESTService} from "./module/profile/service/ProfileRESTService";
import {CommunitySettingsModalModel} from "./module/community/component/Modal/CommunitySettingsModal/model";
import {AuthRESTService} from "./module/auth/service/AuthRESTService";
import {FeedbackComponent} from "./module/feedback/index";
import {PostRESTService} from "./module/post/service/PostRESTService";
import {PostAttachmentRESTService} from "./module/post-attachment/service/PostAttachmentRESTService";
import {ProfileCachedIdentityMap} from "./module/profile/service/ProfileCachedIdentityMap";

@Component({
    selector: 'cass-bootstrap',
    template: require('./template.html'),
    providers: [
        ModalService,
        MessageBusService,
        AuthService,
        AuthRESTService,
        AuthComponentService,
        CommunityModalService,
        CommunityRESTService,
        CommunityService,
        ThemeService,
        ProfileSwitcherService,
        ProfileRESTService,
        CollectionRESTService,
        CommunitySettingsModalModel,
        PostRESTService,
        PostAttachmentRESTService,
        ProfileCachedIdentityMap,
    ],
    directives: [
        ROUTER_DIRECTIVES,
        CORE_DIRECTIVES,
        MessageBusNotifications,
        AuthComponent,
        AccountComponent,
        ProfileComponent,
        SidebarComponent,
        CommunityComponent,
        FeedbackComponent,
        RouterOutlet
]
})
@RouteConfig([
    {
        name: 'Landing',
        path: '/',
        component: LandingComponent,
        useAsDefault: true
    },
    {
        name: 'Html',
        path: '/html/...',
        component: HtmlComponent,
    },
    {
        name: 'Profile',
        path: '/profile/...',
        component: ProfileRootRoute
    },
    {
        name: 'Community',
        path: '/community/...',
        component: CommunityRoute
    }
])
export class App {
    constructor(private authService: AuthService) {
        // Do not(!) remove authService dependency.
    }

    static version(): string {
        return require('./../package.json').version;
    }
}

console.log(`CASS Frontend App: ver${App.version()}`);