import {Component, Input} from "@angular/core";
import {Palette} from "../../../colors/definitions/entity/Palette";
import {FrontlineService} from "../../../frontline/service/FrontlineService";

@Component({
    template: require('./template.jade'),
    styles: [
        require('./style.shadow.scss')
    ],selector: 'cass-color-picker'})

export class ColorPicker
{
    
    private palettes: Palette[] = [];

    @Input('pickedColor') pickedColor;

    pickColor(palette: Palette){
        this.pickedColor = palette;
    }

    constructor(frontline: FrontlineService) {
        let palettes = frontline.session.config.palettes;


        for(let palette of palettes) {
            this.palettes.push(palette);
        }
    }
}