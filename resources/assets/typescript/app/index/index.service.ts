import { Injectable } from '@angular/core';
import { Http } from "@angular/http";

@Injectable()
export class IndexService
{
    constructor(private _http: Http) {}

    getRecords(url) {
        return this._http.get(url).map(res => res.json().models);
    }
}
