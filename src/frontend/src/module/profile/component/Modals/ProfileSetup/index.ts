import {Component, Input, EventEmitter, Output} from "angular2/core";

import {ModalComponent} from "../../../../modal/component/index";
import {ProfileSetupModel} from "./model";
import {ProfileSetupScreenGreetings} from "./Screen/ProfileSetupScreenGreetings/index";
import {ProfileSetupScreenGender} from "./Screen/ProfileSetupScreenGender/index";
import {ProfileSetupScreenImage} from "./Screen/ProfileSetupScreenImage/index";
import {ProfileSetupScreenInterests} from "./Screen/ProfileSetupScreenInterests/index";
import {ProfileSetupScreenExpertIn} from "./Screen/ProfileSetupScreenExpertIn/index";
import {ScreenControls} from "../../../../util/classes/ScreenControls";
import {ProfileRESTService} from "../../../service/ProfileRESTService";
import {ModalBoxComponent} from "../../../../modal/component/box/index";
import {LoadingLinearIndicator} from "../../../../form/component/LoadingLinearIndicator/index";
import {ProfileEntity} from "../../../definitions/entity/Profile";
import {Observable} from "rxjs/Observable";
import {MessageBusService} from "../../../../message/service/MessageBusService/index";
import {MessageBusNotificationsLevel} from "../../../../message/component/MessageBusNotifications/model";

enum ProfileSetupScreen {
    Welcome = <any>"Welcome",
    Gender = <any>"Gender",
    Greetings = <any>"Greetings",
    Image = <any>"Image",
    Interests = <any>"Interests",
    ExpertIn = <any>"ExpertIn",
    Saving = <any>"Saving",
    Finish = <any>"Finish"
}

@Component({
    selector: 'cass-profile-setup',
    template: require('./template.html'),
    styles: [
        require('./style.shadow.scss')
    ],
    providers: [
        ProfileSetupModel,
        ProfileRESTService
    ],
    directives: [
        ModalComponent,
        ModalBoxComponent,
        LoadingLinearIndicator,
        ProfileSetupScreenGreetings,
        ProfileSetupScreenGender,
        ProfileSetupScreenImage,
        ProfileSetupScreenInterests,
        ProfileSetupScreenExpertIn
    ]
})

export class ProfileSetup
{
    @Input('profile') profile: ProfileEntity;

    @Output('success') successEvent = new EventEmitter<ProfileEntity>();
    @Output('close') closeEvent = new EventEmitter<ProfileEntity>();

    public screens: ScreenControls<ProfileSetupScreen> = new ScreenControls<ProfileSetupScreen>(ProfileSetupScreen.Welcome, (sc: ScreenControls<ProfileSetupScreen>) => {
        sc.add({ from: ProfileSetupScreen.Welcome, to: ProfileSetupScreen.Gender })
          .add({ from: ProfileSetupScreen.Gender, to: ProfileSetupScreen.Greetings })
          .add({ from: ProfileSetupScreen.Greetings, to: ProfileSetupScreen.Image })
          .add({ from: ProfileSetupScreen.Image, to: ProfileSetupScreen.Interests })
          .add({ from: ProfileSetupScreen.Interests, to: ProfileSetupScreen.ExpertIn })
          .add({ from: ProfileSetupScreen.ExpertIn, to: ProfileSetupScreen.Saving })
          .add({ from: ProfileSetupScreen.Saving, to: ProfileSetupScreen.Finish });
    });

    constructor(
        private model: ProfileSetupModel,
        private service: ProfileRESTService,
        private messages: MessageBusService
    ) {}

    ngAfterViewInit() {
        this.model.specifyProfile(this.profile);
    }

    close() {
        this.closeEvent.emit(this.profile);
    }

    nextScreen() {
        this.screens.next();

        if(this.screens.isOn(ProfileSetupScreen.Saving)) {
            this.performSaveChanges();
        }
    }

    prevScreen() {
        this.screens.previous();
    }

    performSaveChanges() {
        let profileId = this.model.getProfile().id;

        Observable.forkJoin([
            this.service.setGender(profileId, {
                gender: this.model.gender
            }),
            this.service.setInterestingIn(profileId, {
                theme_ids: this.model.expertIn
            }),
            this.service.setExpertIn(profileId, {
                theme_ids: this.model.expertIn
            })
        ]).subscribe(
            success => {
                this.messages.push(MessageBusNotificationsLevel.Info, 'Ваши данные сохранены');
                this.close();
            },
            error => {
                this.screens.goto(ProfileSetupScreen.ExpertIn);
                this.messages.push(MessageBusNotificationsLevel.Warning, 'Ваши данные не были сохранены')
            }
        )
    }
}