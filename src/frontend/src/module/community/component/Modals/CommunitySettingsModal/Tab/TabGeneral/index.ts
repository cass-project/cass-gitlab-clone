import {Component} from "@angular/core";

import {CommunitySettingsModalModel} from "../../model";

@Component({
    selector: 'cass-community-settings-tab-general',
    template: require('./template.jade'),
    styles: [
        require('./style.shadow.scss')
    ]
})
export class GeneralTab
{
    constructor(public model: CommunitySettingsModalModel) {
        this.isCommunitySettingsModalThemeEnabled = !!model.theme_id;
        if(this.isCommunitySettingsModalThemeEnabled)
            this.selectedThemeId.push(model.theme_id);
    }

    private deleteRequest: CommunityDeleteRequestControls = new CommunityDeleteRequestControls();
    private isCommunitySettingsModalThemeEnabled: boolean;
    private selectedThemeId: number[] = [];

    communitySettingsModalThemeEnabledChange($event: boolean) {
        if($event === false) {
            delete this.model['theme_id'];

            this.model.public_options.public_enabled = false;
            this.model.public_options.moderation_contract = false;
        }
    }

    communitySettingsModalThemeEnablePublicChange($event: boolean) {
        if($event===false) {
            this.model.public_options.moderation_contract = false;
        }
    }

    updateThemeId(themeIds: number[]){
        if(themeIds.length > 0) {
            this.model.theme_id = themeIds[0];
        } else {
            delete this.model['theme_id'];
        }
    }
}

class CommunityDeleteRequestControls
{
    private buttonDisabled: boolean = false;
    public stage: DeleteRequestStage = DeleteRequestStage.NoRequest;

    request() {
        this.buttonDisabled = true;

        setTimeout(() => {
            this.stage = DeleteRequestStage.PendingConfirmation;
            this.buttonDisabled = false;
        }, 1000);
    }

    confirm() {
        this.stage = DeleteRequestStage.Confirmed;
    }

    cancel() {
        this.stage = DeleteRequestStage.NoRequest;
    }

    isButtonDisabled() {
        return this.buttonDisabled;
    }

    isRequested() {
        return this.stage === DeleteRequestStage.PendingConfirmation;
    }

    isConfirmed() {
        return this.stage === DeleteRequestStage.Confirmed;
    }
}

enum DeleteRequestStage
{
    NoRequest,
    PendingConfirmation,
    Confirmed
}