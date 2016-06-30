import {Component, Injectable, Output, EventEmitter} from "angular2/core";

import {UploadImageService} from "../../../../../../form/component/UploadImage/service";
import {UploadProfileImageStrategy} from "../../../../../util/UploadProfileImageStrategy";
import {ProfileImage} from "../../../../ProfileImage/index";
import {UploadImageModal} from "../../../../../../form/component/UploadImage/index";
import {ProfileRESTService} from "../../../../../service/ProfileRESTService";
import {ModalControl} from "../../../../../../util/classes/ModalControl";
import {ProfileSetupModel} from "../../model";
import {DeleteProfileImageResponse200} from "../../../../../definitions/paths/image-delete";

@Component({
    selector: 'cass-profile-setup-screen-image',
    template: require('./template.jade'),
    styles: [
        require('./style.shadow.scss')
    ],
    providers: [
        UploadImageService,
    ],
    directives: [
        ProfileImage,
        UploadImageModal,
    ]
})

@Injectable()
export class ProfileSetupScreenImage
{
    @Output('back') backEvent = new EventEmitter<ProfileSetupModel>();
    @Output('next') nextEvent = new EventEmitter<ProfileSetupModel>();

    upload: ModalControl = new ModalControl();
    isDeleting: boolean = false;

    constructor(
        private model: ProfileSetupModel,
        private uploadImageService: UploadImageService, 
        private profileRESTService: ProfileRESTService
    ) {
        uploadImageService.setUploadStrategy(new UploadProfileImageStrategy(model.getProfile().id));
    }

    next() {
        this.nextEvent.emit(this.model);
    }

    back() {
        this.backEvent.emit(this.model);
    }

    skip() {
        this.nextEvent.emit(this.model);
    }

    getProfileImage(): string {
        return this.model.getProfileImage().public_path;
    }

    deleteAvatar(){
        this.isDeleting = true;
        
        this.profileRESTService
            .deleteAvatar(this.model.getProfile().id)
            .map(res => res.json())
            .subscribe(
                (response: DeleteProfileImageResponse200) => {
                    this.isDeleting = false;
                },
                (error) => {
                    this.isDeleting = false;
                }
            )
    }

    uploadProfileImage() {
        this.upload.open();
    }
}