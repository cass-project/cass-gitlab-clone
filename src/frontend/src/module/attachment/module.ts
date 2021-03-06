import {Attachment} from "./component/Elements/Attachment/index";
import {AttachmentRESTService} from "./service/AttachmentRESTService";
import {ATTACHMENT_ERROR_DIRECTIVES} from "./component/Elements/AttachmentError/index";
import {ATTACHMENT_IMAGE_DIRECTIVES} from "./component/Elements/AttachmentImage/index";
import {ATTACHMENT_LINK_DIRECTIVES} from "./component/Elements/AttachmentLink/index";
import {ATTACHMENT_WEBM_DIRECTIVES} from "./component/Elements/AttachmentWebm/index";
import {ATTACHMENT_YOUTUBE_DIRECTIVES} from "./component/Elements/AttachmentYouTube/index";
import {ATTACHMENT_PAGE_DIRECTIVES} from "./component/Elements/AttachmentPage/index";
import {AttachmentWebmNotifier} from "./component/Elements/AttachmentWebm/notify";
import {AttachmentYoutubeNotifier} from "./component/Elements/AttachmentYouTube/notify";

export const CASSAttachmentModule = {
    declarations: [
        Attachment,
        ATTACHMENT_ERROR_DIRECTIVES,
        ATTACHMENT_IMAGE_DIRECTIVES,
        ATTACHMENT_PAGE_DIRECTIVES,
        ATTACHMENT_LINK_DIRECTIVES,
        ATTACHMENT_WEBM_DIRECTIVES,
        ATTACHMENT_YOUTUBE_DIRECTIVES,
    ],
    providers: [
        AttachmentRESTService,
        AttachmentWebmNotifier,
        AttachmentYoutubeNotifier,
    ]
};