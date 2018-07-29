import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { NgbDateStruct } from '@ng-bootstrap/ng-bootstrap';

@Injectable({
  providedIn: 'root'
})
export class DateService {

  private form = new FormData;
  
  constructor(private http: HttpClient) { 
    
    this.form.append('controller', 'Date');
  
  }
  
  public calculateDate(startDate: string, endDate: string) {
    
    
    this.form.append('startDate' , startDate);
    this.form.append('endDate' , endDate);
    this.form.append('operation', 'calculateDate');
    
    
    this.http.post('http://localhost/dispatch.php', this.form)
      .subscribe((data) => {
        
        console.log('Some Data coming back ', data);
      }, (error) => {
        console.log('Error! ', error);
      });
  }
}
