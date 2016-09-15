import {Component} from "@angular/core";
import {FeedService} from "../../../feed/service/FeedService/index";
import {PublicContentSource} from "../../../feed/service/FeedService/source/public/PublicContentSource";
import {Stream} from "../../../feed/service/FeedService/stream";
import {PublicService} from "../../service";
import {PostIndexedEntity} from "../../../post/definitions/entity/Post";

@Component({
    template: require('./template.jade'),
    styles: [
        require('./style.shadow.scss')
    ],
    providers: [
        FeedService,
        PublicContentSource,
    ]
})
export class ContentRoute
{
    constructor(
        private catalog: PublicService,
        private service: FeedService<PostIndexedEntity>,
        private source: PublicContentSource
    ) {
        catalog.source = 'content';
        catalog.injectFeedService(service);
        
        service.provide(source, new Stream<PostIndexedEntity>());
        service.update();
    }
}