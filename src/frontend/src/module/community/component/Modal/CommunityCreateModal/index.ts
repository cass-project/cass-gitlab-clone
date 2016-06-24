import {Component, EventEmitter, Output} from "angular2/core";

import {CommunityCreateModalModel} from "./model";

import {ScreenGeneral} from "./Screen/ScreenGeneral";
import {ScreenTheme} from "./Screen/ScreenTheme";
import {ScreenImage} from "./Screen/ScreenImage";
import {ScreenFeatures} from "./Screen/ScreenFeatures";
import {ScreenProcessing} from "./Screen/ScreenProcessing";
import {ScreenComplete} from "./Screen/ScreenComplete";

import {ModalComponent} from "../../../../modal/component";
import {ModalBoxComponent} from "../../../../modal/component/box";
import {ScreenControls} from "../../../../util/classes/ScreenControls";
import {AuthService} from "../../../../auth/service/AuthService";

enum CreateStage {
    General = <any>"General",
    Theme = <any>"Theme",
    Image = <any>"Image",
    Features = <any>"Features",
    Processing = <any>"Processing",
    Complete = <any>"Complete"
}

@Component({
    selector: 'cass-community-create-modal',
    template: require('./template.jade'),
    providers: [
        CommunityCreateModalModel
    ],
    directives: [
        ModalComponent,
        ModalBoxComponent,
        ScreenGeneral,
        ScreenTheme,
        ScreenImage,
        ScreenFeatures,
        ScreenProcessing,
        ScreenComplete
    ]
})

export class CommunityCreateModal
{
    public screens: ScreenControls<CreateStage> = new ScreenControls<CreateStage>(CreateStage.General, (sc: ScreenControls<CreateStage>) => {
        sc.add({ from: CreateStage.General, to: CreateStage.Theme })
          .add({ from: CreateStage.Theme, to: CreateStage.Features })
          .add({ from: CreateStage.Features, to: CreateStage.Image })
          .add({ from: CreateStage.Image, to: CreateStage.Processing })
          .add({ from: CreateStage.Processing, to: CreateStage.Complete })
        ;
    });

    @Output('close') closeEvent = new EventEmitter<CommunityCreateModal>();

    ngOnInit() {
        if(!AuthService.isSignedIn()) {
            this.closeEvent.emit(this);
        }
    }

    isHeaderVisible() {
        return !~([CreateStage.Processing]).indexOf(this.screens.current);
    }

    next() {
        this.screens.next();
    }

    close() {
        this.closeEvent.emit(this);
    }
}