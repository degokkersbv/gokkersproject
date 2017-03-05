using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace DeGokkers
{
    public class Bet
    {
        private int _amount;

        public int Amount
        {
            get { return _amount; }
            set { _amount = value; }
        }

        private int _storkNumber;

        public int storkNumber
        {
            get { return _storkNumber; }
            set { _storkNumber = value; }
        }

        private Guy _bettor;

        public Guy Bettor
        {
            get { return _bettor; }
            set { _bettor = value; }
        }

        public string GetDescription()
        {
            if (this._amount == 0) // mean initially user doesnot have any bucks or bet placed
                return this._bettor.Name + " hasn't placed any bet";
            else // else return what he placed and on what dog
                return this._bettor.Name + " placed " + this._bettor.MyBet._amount.ToString() + "$ on Stork # " + this._bettor.MyBet.storkNumber.ToString();
        }

        public int Payout(int winningStorkNo)
        {
            if (this._bettor.MyBet.storkNumber == winningStorkNo)
                return this._amount;
            else
                return -this._amount;
        }
    }
}
