import  { Injectable } from '@angular/core';
import  { Headers, Http, Response } from '@angular/http';
import 'rxjs/Rx';
import { Observable } from 'rxjs/Observable';

@Injectable()
export class ServersService {
    private myhost: string = "http://207.172.210.234/rest/";
    private saveServersInfo: string = "saveserversinfo.php";
    private getServersInfo: string = "getserversinfo.php";
    private getStaticTitle: string = "getstatictitle.php";

    constructor(private http: Http) {}

    // as long as we dont Subscribe no call is made
    storeServers(servers: any[]) {
        const headers = new Headers({
            'Content-Type': 'application/json'
        });

        return this.http.post(this.myhost+this.saveServersInfo,
                                servers,
                                { headers: headers });
    }

    getServers() {
        return this.http.get(this.myhost+this.getServersInfo)
        .map(
            (response: Response) => {
                const data = response.json();
                // // example of how to transform data with map
                // for (const server of data) {
                //     server.name = 'FETCHED_' + server.name;
                // }
                return data;
            }
        )
        .catch(
            (error: Response) => {
                console.log(error);
                return Observable.throw("Something went wrong!");
            }
        );
    }


    getAppName() {
        return this.http.get(this.myhost+this.getStaticTitle)
        .map(
            (response: Response) => {
                const data = response.json();
                console.log(data);
                return data.appName;
            }
        );
    }
}
