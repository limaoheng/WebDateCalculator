import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { NgbDateStruct } from '@ng-bootstrap/ng-bootstrap';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class DateService {
  
  constructor(private http: HttpClient) {
  }
  
  public calculateDate(startDate: string, endDate: string, startTimeZone: string, endTimeZone: string): Observable<Object> {
    
    const form = this.getRequestBody();
    form.append('startDate' , startDate);
    form.append('endDate' , endDate);
    form.append('startTimeZone' , startTimeZone);
    form.append('endTimeZone' , endTimeZone);
    form.append('operation', 'calculateDate');
    return this.http.post('http://localhost/dispatch.php', form);
      
  }
  
  public getSupportedTimeZones(): Observable<Object> {
    const form = this.getRequestBody();
    form.append('operation', 'getSupportedTimeZones');
    return this.http.post('http://localhost/dispatch.php', form);
  }
  
  private getRequestBody() {
    const form = new FormData;
    form.append('controller', 'Date');
    return form;
  }
 
}
