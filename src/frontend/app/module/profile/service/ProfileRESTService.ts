import {UploadProfileImageStrategy} from "../util/UploadProfileImageStrategy";

import {Injectable} from 'angular2/core';
import {Http, URLSearchParams} from 'angular2/http';
import {AuthService} from "../../auth/service/AuthService";
import {AbstractRESTService} from "../../common/service/AbstractRESTService";

@Injectable()
export class ProfileRESTService extends AbstractRESTService{
    private xmlRequest = new XMLHttpRequest();

    public tryNumber:number = 0;
    public progressBar:number = 0;


    getProfileById(profileId){
        let url = `/backend/api/profile/${profileId}/get`;

        return this.handle(this.http.get(url));
    }

    createNewProfile(){
        let url = `/backend/api/protected/profile/create`;
        return this.handle(this.http.put(url, JSON.stringify({
            accountId: 'current'
        })));
    }

    editPersonal(profile) {
        let url = `/backend/api/protected/profile/${this.currentProfile.id}/edit-personal/`;
        let entity = AuthService.getAuthToken().getCurrentProfile().entity;
        let greetings = entity.profile.greetings;

        greetings.greetings = profile.greetings.greetings_method;
        greetings.last_name = profile.greetings.last_name;
        greetings.first_name = profile.greetings.first_name;
        greetings.middle_name = profile.greetings.middle_name;
        greetings.nick_name = profile.greetings.nickname;
        entity.profile.gender = profile.gender;

        return this.handle(this.http.post(url, JSON.stringify({
            gender: profile.gender.string,
            greetings_method: profile.greetings.greetings_method,
            last_name: profile.greetings.last_name,
            first_name: profile.greetings.first_name,
            middle_name: profile.greetings.middle_name,
            nickname: profile.greetings.nickname
        })));
    }

    updateExpertThemes(expertIn) {
        let url = `/backend/api/protected/profile/${this.currentProfile.id}/expert-in`;

        AuthService.getAuthToken().getCurrentProfile().entity.profile.expert_in_ids = (JSON.parse(JSON.stringify(expertIn)));

        return this.handle(this.http.put(url, JSON.stringify({
            theme_ids: expertIn
        })));
    }

    updateInterestThemes(interestingIn) {
        let url = `/backend/api/protected/profile/${this.currentProfile.id}/interesting-in`;

        AuthService.getAuthToken().getCurrentProfile().entity.profile.interesting_in_ids = (JSON.parse(JSON.stringify(interestingIn)));

        return this.handle(this.http.put(url, JSON.stringify({
            theme_ids: interestingIn
        })));
    }


    switchProfile(profileId){
        let url = `/backend/api/protected/profile/${profileId}/switch/`;

        return this.handle(this.http.post(url, JSON.stringify('')));
    }

    deleteProfile(profileId){
        let url = `/backend/api/protected/profile/${profileId}/delete`;

        return this.handle(this.http.delete(url));
    }

    requestAccountDeleteCancel() {
        let url = `/backend/api/protected/account/cancel-request-delete`;

        return this.handle(this.http.delete(url));
    }

    requestAccountDelete() {
        let url = `/backend/api/protected/account/request-delete`;

        return this.handle(this.http.put(url, JSON.stringify('')));
    }

    changePassword(changePasswordStn) {
        let url = `/backend/api/protected/account/change-password`;

        return this.handle(this.http.post(url, JSON.stringify({
            old_password: changePasswordStn.old_password,
            new_password: changePasswordStn.new_password
        })));
    }

    deleteAvatar() {
        let url = `/backend/api/protected/profile/${this.currentProfile.id}/image-delete`;

        return this.handle(this.http.delete(url));
    }


    cancelAvatarUpload(){
        this.xmlRequest.abort();
    }

    avatarUpload(file, model, modal) {
        let crop = {
            start: {
                x: model.x,
                y: model.y
            },
            end: {
                x: model.width + model.x,
                y: model.height + model.y
            }
        };

        this.tryNumber++;
        let url = `/backend/api/protected/profile/${this.currentProfile.id}/image-upload/crop-start/${crop.start.x}/${crop.start.y}/crop-end/${crop.end.x}/${crop.end.y}`;
        let formData = new FormData();
        formData.append("file", file);

        this.xmlRequest.open("POST", url);
        this.xmlRequest.upload.onprogress = (e) => {
            if (e.lengthComputable) {
                this.progressBar = Math.floor((e.loaded / e.total) * 100);
                modal.progress.update(this.progressBar);
            }
        };

        this.xmlRequest.send(formData);

        this.xmlRequest.onreadystatechange = () => {
            if (this.xmlRequest.readyState === 4) {
                if (this.xmlRequest.status === 200) {
                    this.currentProfile.image = JSON.parse(this.xmlRequest.responseText).image;
                    }
                    modal.progress.complete();
                    if(modal.close){
                        modal.close();
                    } else {
                        modal.screen.next();
                    }
                    this.progressBar = 0;
                    this.tryNumber = 0;
                }
                /* ToDo: Сделать нормальный метод повтора загрузки с учетом "Отмены загрузки". */
            }
        }
}