import {Component, Input} from "@angular/core";
import {QueryTarget, queryImage} from "../../../../avatar/functions/query";
import {CommunityEntity} from "../../../definitions/entity/Community";
import {ThemeService} from "../../../../theme/service/ThemeService";
import {Router} from "@angular/router";

@Component({
    template: require('./template.jade'),
    styles: [
        require('./style.shadow.scss')
    ],selector: 'cass-community-card'})
export class CommunityCard
{
    @Input('community') entity: CommunityEntity;

    constructor(private themeService: ThemeService,
                private router: Router) {}
    
    getTheme(){
       return this.themeService.findById(this.entity.theme.id);
    }

    getCommunityTitle(): string {
        return this.entity.title;
    }

    getCommunityDescription(): string {
        return this.entity.description;
    }

    getImageURL(): string {
        return queryImage(QueryTarget.Card, this.entity.image).public_path;
    }

    hasTheme() {
        return this.entity.theme.has;
    }

    goCommunity() {
        this.router.navigate(['/Community', 'Community', { 'sid': this.entity.sid }]);
    }
}