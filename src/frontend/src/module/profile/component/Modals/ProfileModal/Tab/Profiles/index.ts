import {Component} from "@angular/core";

import {ProfilesTabService} from "./service";
import {ThemeService} from "../../../../../../theme/service/ThemeService";
import {ProfileModalModel} from "../../model";

@Component({
    selector: 'cass-profile-modal-tab-profiles',
    template: require('./template.html'),
    styles: [
        require('./style.shadow.scss')
    ],
    providers: [
        ProfilesTabService
    ]
})
export class ProfileModalProfilesTab
{
    constructor(private service: ProfilesTabService, private themeService: ThemeService, private model: ProfileModalModel) {}
}