import {Component, OnChanges, Input, Output, EventEmitter} from "@angular/core";

import {AttachmentEntity} from "../../../../../definitions/entity/AttachmentEntity";
import {UnknownAttachmentMetadata} from "../../../../../definitions/entity/metadata/UnknownAttachmentMetadata";
import {ViewOptionValue} from "../../../../../../feed/service/FeedService/options/ViewOption";
import {AttachmentLinkHelper} from "../../helper";

@Component({
    selector: 'cass-attachment-link-list',
    template: require('./template.jade'),
    styles: [
        require('./style.shadow.scss'),
    ]
})
export class AttachmentLinkList implements OnChanges
{
    @Input('attachment') attachment: AttachmentEntity<UnknownAttachmentMetadata>;
    @Input('viewMode') viewMode: ViewOptionValue = ViewOptionValue.Feed;
    @Output('open') openEvent: EventEmitter<AttachmentEntity<UnknownAttachmentMetadata>> = new EventEmitter<AttachmentEntity<UnknownAttachmentMetadata>>();

    private helper: AttachmentLinkHelper;

    ngOnChanges() {
        this.helper = new AttachmentLinkHelper(this.attachment);
    }

    open(attachment: AttachmentEntity<UnknownAttachmentMetadata>): boolean {
        this.openEvent.emit(attachment);

        return false;
    }
}