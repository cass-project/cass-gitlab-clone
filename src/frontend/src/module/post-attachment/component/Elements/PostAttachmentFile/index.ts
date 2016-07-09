import {Component, Input} from "angular2/core";
import {PostAttachmentLink} from "../PostAttachmentLink/index";
import {PostAttachmentImage} from "../PostAttachmentImage/index";
import {PostAttachmentWebm} from "../PostAttachmentWebm/index";
import {PostAttachmentYouTube} from "../PostAttachmentYouTube/index";
import {PostAttachmentEntity} from "../../../definitions/entity/PostAttachment";

@Component({
    selector: 'cass-post-attachment-file',
    template: require('./template.jade'),
    styles: [
        require('./style.shadow.scss')
    ],
    directives: [
        PostAttachmentFile,
        PostAttachmentLink,
        PostAttachmentImage,
        PostAttachmentWebm,
        PostAttachmentYouTube,
    ]
})
export class PostAttachmentFile
{
    @Input('attachment') attachment: PostAttachmentEntity;

    is(code: string) {
        return code === this.attachment.attachment_type;
    }
}