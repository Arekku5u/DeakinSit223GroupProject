using Microsoft.EntityFrameworkCore;
namespace WebApplication.Models
{
    public class SchedulerContext : DbContext
    {
        public SchedulerContext(DbContextOptions<SchedulerContext> options)
            : base(options)
        {
        }
        public DbSet<SchedulerEvent> Events { get; set; }
        
    }
}