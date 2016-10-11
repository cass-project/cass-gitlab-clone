import {ElementRef} from "@angular/core";
import {UIStrategy} from "./ui.strategy";

export class GridStrategy implements UIStrategy
{
    content: ElementRef;

    constructor(private element: ElementRef)
    {
        this.content = element;
    }

    up()
    {
        console.log(this.content);
    }

    down()
    {
        console.log(this.content);
    }

    left()
    {
        console.log(this.content);
    }

    right()
    {
        console.log(this.content);
    }

    top()
    {
        this.content.nativeElement.scrollTop = 0;
    }

    bottom()
    {
        this.content.nativeElement.scrollTop = this.content.nativeElement.scrollHeight;
    }

    enter()
    {
        console.log(this.content);
    }
}