import {Injectable} from "@angular/core";
import {Observable} from "rxjs/Rx";

import {AccountRESTService} from "./AccountRESTService";
import {AppAccessResponse200} from "../definitions/paths/app-access";

@Injectable()
export class AccountService
{
    constructor(private rest: AccountRESTService) {}

    public appAccess() : Observable<AppAccessResponse200>
    {
        return Observable.create(observer => {
            this.rest.appAccess().subscribe(
                data => {
                    observer.next(data);
                    observer.complete();
                },
                error => {
                    observer.error(error);
                }
            )
        })
    }
}
