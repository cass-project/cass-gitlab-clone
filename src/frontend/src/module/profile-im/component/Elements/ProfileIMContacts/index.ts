import {Component} from "angular2/core";
import {ROUTER_DIRECTIVES} from "angular2/router";

import {ContactEntity} from "../../../../contact/definitions/entity/Contact";
import {ContactService} from "../../../../contact/service/ContactService";
import {ProfileImage} from "../../../../profile/component/Elements/ProfileImage/index";

@Component({
    selector: 'cass-profile-im-messages',
    template: require('./template.jade'),
    styles: [
        require('./style.shadow.scss')
    ],
    directives:[
        ROUTER_DIRECTIVES,
        ProfileImage
    ]
})

export class ProfileIMContacts
{
    private contacts:ContactEntity[];
    
    constructor(
        private contactService:ContactService
    ) {
        contactService.listContacts().subscribe(
            data => this.contacts = data.entities
        );
    }
}