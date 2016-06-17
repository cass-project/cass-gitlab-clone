import {Component} from "angular2/core";

import {CommunityService} from "../../service/CommunityService";
import {CommunityComponentService} from "../../service";

@Component({
    selector: 'cass-community-menu',
    template: require('./template.jade'),
    styles: [
        require('./style.shadow.scss')
    ]
})
export class CommunityMenuComponent
{
    constructor(private service: CommunityService, private modalsService: CommunityComponentService){}
}