import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { NgbDateStruct } from '@ng-bootstrap/ng-bootstrap';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class DateService {

  private form = new FormData;
  
  constructor(private http: HttpClient) { 
    
    this.form.append('controller', 'Date');
  
  }
  
  public calculateDate(startDate: string, endDate: string): Observable<Object> {
    
    
    this.form.append('startDate' , startDate);
    this.form.append('endDate' , endDate);
    this.form.append('operation', 'calculateDate');
    return this.http.post('http://localhost/dispatch.php', this.form);
      
  }
 
}
