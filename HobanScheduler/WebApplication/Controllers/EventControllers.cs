using System.Collections.Generic;
using System.Linq;
using Microsoft.AspNetCore.Mvc;
using WebApplication.Models;

namespace WebApplication.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class EventsController : ControllerBase
    {
        private readonly SchedulerContext _context;

        public EventsController(SchedulerContext context)
        {
            _context = context;
        }

        // GET api/events
        [HttpGet]
        public IEnumerable<WebAPIEvent> Get()
        {
            return _context.Events
                .ToList()
                .Select(e => (WebAPIEvent) e);
        }

        // GET api/events/5
        [HttpGet("{id}")]
        public WebAPIEvent Get(int id)
        {
            return (WebAPIEvent) _context
                .Events
                .Find(id);
        }

        // POST api/events
        [HttpPost]
        public ObjectResult Post([FromForm] WebAPIEvent apiEvent)
        {
            var newEvent = (SchedulerEvent) apiEvent;

            _context.Events.Add(newEvent);
            _context.SaveChanges();
            // delete a single occurrence from a recurring series
            var resultAction = "inserted";
            if (newEvent.RecType == "none")
            {
                resultAction = "deleted";
            }

            return Ok(new
            {
                tid = newEvent.Id,
                action = "resultAction"
            });
        }

        // PUT api/events/5
        [HttpPut("{id}")]
        public ObjectResult Put(int id, [FromForm] WebAPIEvent apiEvent)
        {
            var updatedEvent = (SchedulerEvent) apiEvent;
            var dbEveht = _context.Events.Find(id);
            dbEveht.Name = updatedEvent.Name;
            dbEveht.StartDate = updatedEvent.StartDate;
            dbEveht.EndDate = updatedEvent.EndDate;
            dbEveht.EventPID = updatedEvent.EventPID;
            dbEveht.RecType = updatedEvent.RecType;
            dbEveht.EventLength = updatedEvent.EventLength;

            if (!string.IsNullOrEmpty(updatedEvent.RecType) && updatedEvent.RecType != "none")
            {
                //all modified occurrences must be deleted when we update a recurring series
                //https://docs.dhtmlx.com/scheduler/server_integration.html#recurringevents

                _context.Events.RemoveRange(
                    _context.Events.Where(e => e.EventPID == id)
                );
            }

            _context.SaveChanges();

            return Ok(new
            {
                action = "updated"
            });
        }

        // DELETE api/events/5
        [HttpDelete("{id}")]
        public ObjectResult DeleteEvent(int id)
        {
            var e = _context.Events.Find(id);
            if (e != null)
            {
                //some logic specific to recurring events support
                //https://docs.dhtmlx.com/scheduler/server_integration.html#recurringevents

                if (e.EventPID != default(int))
                {
                    // deleting a modified occurrence from a recurring series
                    // If an event with the event_pid value was deleted, it should be updated 
                    // with rec_type==none instead of deleting.

                    e.RecType = "none";
                }
                else
                {
                    //if a recurring series deleted, delete all modified occurrences of the series
                    if (!string.IsNullOrEmpty(e.RecType) && e.RecType != "none")
                    {
                        //all modified occurrences must be deleted when we update recurring series
                        //https://docs.dhtmlx.com/scheduler/server_integration.html#recurringevents
                        _context.Events.RemoveRange(
                            _context.Events.Where(ev => ev.EventPID == id)
                        );
                    }

                    _context.Events.Remove(e);
                }

                _context.SaveChanges();
            }

            return Ok(new
            {
                action = "deleted"
            });
        }
    }
}