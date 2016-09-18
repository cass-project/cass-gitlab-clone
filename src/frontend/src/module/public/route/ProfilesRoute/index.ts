import {Component} from "@angular/core";
import {FeedService} from "../../../feed/service/FeedService/index";
import {Stream} from "../../../feed/service/FeedService/stream";
import {PublicService} from "../../service";
import {PublicProfilesSource} from "../../../feed/service/FeedService/source/public/PublicProfilesSource";
import {ProfileIndexedEntity} from "../../../profile/definitions/entity/Profile";

@Component({
    template: require('./template.jade'),
    styles: [
        require('./style.shadow.scss')
    ]
})
export class ProfilesRoute
{
    constructor(
        private catalog: PublicService,
        private service: FeedService<ProfileIndexedEntity>,
        private source: PublicProfilesSource
    ) {
        catalog.source = 'profiles';
        catalog.injectFeedService(service);
        
        service.provide(source, new Stream<ProfileIndexedEntity>());
        service.update();
    }
}