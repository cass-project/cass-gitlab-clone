export class MessageBusNotificationsModel {
    id:number;
    level:MessageBusNotificationsLevel;
    state:MessageBusNotificationsStates = MessageBusNotificationsStates.Showing;
    timeout:number;
    message:string;
}

export enum MessageBusNotificationsLevel {
    Critical = <any>"critical",
    Warning = <any>"warning",
    Info = <any>"info",
    Debug = <any>"debug",
    Success = <any>"success",
}

export enum MessageBusNotificationsStates {
    Showing = <any>"showing",
    Hidding = <any>"hidding"
}